<?php

namespace App\Validator\Constraints;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class AccountConstraintValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AccountConstraint) {
            throw new UnexpectedTypeException($constraint, AccountConstraint::class);
        }
        if (!$value instanceof Account) {
            throw new UnexpectedTypeException($value, Account::class);
        }

        if (($value->getBalance()) <= 0) {
            $this->context->addViolation("Balance cannot be less than 0.");
        }

        if (empty($value->getAccountNumber())) {
            $this->context->addViolation("Account number is empty");
        }

        $user = $value->getUser();
        $accountRepository = $this->entityManager->getRepository(Account::class);
        $accountCount = $accountRepository->count(['user' => $user]);

        if ($accountCount >= 3) {
            $this->context->addViolation("User cannot have more than 3 accounts.");
        }

        $accountRepository = $this->entityManager->getRepository(Account::class);
        $existingAccount = $accountRepository->findOneBy(['accountNumber' => $value]);

        if ($existingAccount) {
            $this->context->addViolation("An account with this account number already exists.");
        }
    }
}
