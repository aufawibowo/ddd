<?php

namespace A7Pro\Wallet\Core\Application\Services\Pay;

use A7Pro\Wallet\Core\Domain\Exceptions\ValidationException;
use A7Pro\Wallet\Core\Domain\Models\Date;
use A7Pro\Wallet\Core\Domain\Models\Transaction;
use A7Pro\Wallet\Core\Domain\Models\TransactionCode;
use A7Pro\Wallet\Core\Domain\Models\TransactionId;
use A7Pro\Wallet\Core\Domain\Models\UserId;
use A7Pro\Wallet\Core\Domain\Repositories\TransactionRepository;
use A7Pro\Wallet\Core\Domain\Repositories\WalletRepository;

class PayService
{
    private WalletRepository $walletRepository;
    private TransactionRepository $transactionRepository;

    public function __construct(WalletRepository $walletRepository, TransactionRepository $transactionRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function execute(PayRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // get sender wallet
        $senderWallet = $this->walletRepository->getByUserId(new UserId($request->senderId));

        // get receiver wallet
        $receiverWallet = $this->walletRepository->getByUserId(new UserId($request->receiverId));

        // create transaction
        $transaction = new Transaction(
            new TransactionId(),
            TransactionCode::createFromTransactionType(Transaction::TYPE_PAY),
            $request->description,
            new Date(),
            $receiverWallet->getId(),
            $senderWallet->getId(),
            $request->amount,
            Transaction::TYPE_PAY
        );

        // validate transaction
        $errors = $transaction->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // save transaction
        $this->transactionRepository->save($transaction);

        // increased balance in receiver wallet
        try {
            $receiverWallet->creditBalance($request->amount);
        } catch (\Exception $e) {
            throw $e;
        }

        // validate receiver wallet
        $errors = $receiverWallet->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // save receiver wallet
        $this->walletRepository->save($receiverWallet);

        // decreased balance in sender wallet
        try {
            $senderWallet->debitBalance($request->amount);
        } catch (\Exception $e) {
            throw $e;
        }

        // validate sender wallet
        $errors = $senderWallet->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // save sender wallet
        $this->walletRepository->save($senderWallet);
    }
}