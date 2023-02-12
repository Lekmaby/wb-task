<?php

class UserEmailChangerService
{
	private \PDO $db;
	private UserEmailSenderInterface $emailSender;
	private LockService $lockService;

	public function __construct(\PDO                     $db,
								UserEmailSenderInterface $emailSender,
								LockService              $lockService)
	{
		$this->db = $db;
		$this->emailSender = $emailSender;
		$this->lockService = $lockService;
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
		$lockKey = 'USERS_' . $userId;
		$this->lockService->setLock($lockKey);

		$oldEmail = $this->getOldEmail();

		if ($email === $oldEmail) {
			$this->lockService->releaseLock($lockKey);
			return;
		}

		$statement = $this->db->prepare('UPDATE users SET email = :email WHERE id = :id');

		$statement->bindParam(':id', $userId, \PDO::PARAM_INT);
		$statement->bindParam(':email', $email, \PDO::PARAM_STR);
		$statement->execute();

		$this->emailSender->sendEmailChangedNotification($oldEmail, $email);

		$this->lockService->releaseLock($lockKey);
	}

	protected function getOldEmail()
	{
		$statement = $this->db->prepare('SELECT email FROM users WHERE id = :id');
		$statement->bindParam(':id', $userId, \PDO::PARAM_INT);
		$statement->execute();
		$item = $statement->fetch();

		return $item['email'];
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

class LockService
{
	private \PDO $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function setLock(string $name, int $timeout = 60): mixed
	{
		$stmt = $this->db->query('SELECT GET_LOCK("' . $name . '", ' . $timeout . ')');
		return $stmt->fetch();
	}

	public function releaseLock(string $name): void
	{
		$this->db->exec('RELEASE_LOCK("' . $name . '");');
	}
}

