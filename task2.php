<?php

class SameEmailException extends Exception
{

}

class EmailSendException extends Exception
{

}

trait Lockable
{
	abstract public function getLockKey(array $params);

	abstract public function getLockableMethods();

	public function __call($method, $arguments)
	{
		if (method_exists($this, $method)) {
			if (!in_array($method, $this->getLockableMethods(), true)) {
				return call_user_func_array([$this, $method], $arguments);
			}

			$lockService = LockService::getInstance();
			$lockKey = $this->getLockKey();
			$lockService->setLock($lockKey);

			try {
				return call_user_func_array([$this, $method], $arguments);
			} finally {
				$lockService->releaseLock($lockKey);
			}
		}
	}
}

class UserEmailChangerService
{
	use Lockable;

	private \PDO $db;
	private UserEmailSenderInterface $emailSender;

	public function __construct(\PDO                     $db,
								UserEmailSenderInterface $emailSender)
	{
		$this->db = $db;
		$this->emailSender = $emailSender;
	}


	/**
	 * @param int $userId
	 * @param string $email
	 *
	 * @return void
	 *
	 * @throws \PDOException
	 */
	public function changeEmail(int $userId, string $email): void
	{
		$oldEmail = $this->getOldEmail();

		if ($email === $oldEmail) {
			throw new SameEmailException($email);
		}

		$statement = $this->db->prepare('UPDATE users SET email = :email WHERE id = :id');

		$statement->bindParam(':id', $userId, \PDO::PARAM_INT);
		$statement->bindParam(':email', $email, \PDO::PARAM_STR);

		$statement->execute();
		$this->emailSender->sendEmailChangedNotification($oldEmail, $email);
	}

	protected function getOldEmail()
	{
		$statement = $this->db->prepare('SELECT email FROM users WHERE id = :id');
		$statement->bindParam(':id', $userId, \PDO::PARAM_INT);
		$statement->execute();
		$item = $statement->fetch();

		return $item['email'];
	}

	public function getLockKey(array $params)
	{
		return 'USERS_' . implode('_', $params);
	}

	public function getLockableMethods(){
		return ['changeEmail'];
	}
}

interface UserEmailSenderInterface
{
	/**
	 * @param string $oldEmail
	 * @param string $newEmail
	 *
	 * @return void
	 *
	 * @throws EmailSendException
	 */
	public function sendEmailChangedNotification(string $oldEmail, string $newEmail): void;
}

final class LockService
{
	private static ?LockService $instance = null;
	private \PDO $db;

	public static function getInstance(\PDO $db = null): LockService
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		if ($db) {
			self::$instance->db = $db;
		}

		return self::$instance;
	}

	public function setLock(string $name, int $timeout = 60): mixed
	{
		return $this->db->query('SELECT GET_LOCK("' . $name . '", ' . $timeout . ')')->fetch();
	}

	public function releaseLock(string $name): void
	{
		$this->db->exec('RELEASE_LOCK("' . $name . '");');
	}

	public function __construct()
	{
	}

	private function __clone()
	{
	}

	public function __wakeup()
	{
		throw new Exception("Cannot unserialize singleton");
	}
}

