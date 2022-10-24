<?php

namespace PaymentApp\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PaymentApp\TransactionModule\Contexts\TransactionStatus;
use PaymentApp\TransactionModule\Repositories\TransactionRepository;
use PaymentApp\UserModule\Repositories\UserRepository;
use pcrov\JsonReader\JsonReader;

class InsertProviderData extends Command
{
    protected JsonReader $jsonReader;
    protected UserRepository $userRepository;
    protected TransactionRepository $transactionRepository;

    protected $insertedUsers = 0;
    protected $insertedTransactions = 0;
    protected $skippedUsers = 0;
    protected $skippedTransactions = 0;
    protected $totalUsers = 0;
    protected $totalTransactions = 0;

    protected $signature = 'insert:provider-data';

    protected $description = 'Import user and transactions data from json to database';

    public function __construct()
    {
        parent::__construct();
        $this->jsonReader = new JsonReader();
        $this->userRepository = new UserRepository();
        $this->transactionRepository = new TransactionRepository();
    }


    public function handle(): void
    {
        $this->importUsers();
        $this->importTransactions();
    }

    private function importUsers()
    {
        $this->info('reading users file...');
        if(!Storage::disk('provider')->exists('users.json')){
            $this->error('users : '.__('file.not_found'));
            return;
        }
        $path = storage_path('provider/users.json');
        $this->jsonReader->open($path);
        $depth = $this->jsonReader->depth(); // Check in a moment to break when the array is done.
        $this->jsonReader->read(); // Step to the first object.
        $this->info('Importing users...');
        do {
            $data = $this->jsonReader->value();

            $this->withProgressBar($data['users'], function ($user) {
                $this->performImportingUsers($user);
            });


        } while ($this->jsonReader->next() && $this->jsonReader->depth() > $depth); // Read each sibling.

        $this->jsonReader->close();

        $this->info('Users imported successfully');
        $this->info('Total users: '.$this->totalUsers);
        $this->info('Inserted users: '.$this->insertedUsers);
        $this->info('Skipped users: '.$this->skippedUsers);
    }

    private function performImportingUsers($data)
    {
        // insert new user only if not exists
        if ($this->userRepository->exists(['short_uuid' => $data['id']])) {
            $this->skippedUsers++;
        }else{
            $this->userRepository->create([
                'balance'=> $data['balance'],
                'currency'=> $data['currency'],
                'email'=> $data['email'],
                'created_at'=> $data['created_at'],
                'short_uuid'=> $data['id'],
            ]);
            $this->insertedUsers++;
        }
        $this->totalUsers++;

    }

    private function importTransactions()
    {
        $this->info('transactions users file...');
        if(!Storage::disk('provider')->exists('transactions.json')){
            $this->error('transactions : '.__('file.not_found'));
            return;
        }
        $path = storage_path('provider/transactions.json');
        $this->jsonReader->open($path);
        $depth = $this->jsonReader->depth(); // Check in a moment to break when the array is done.
        $this->jsonReader->read(); // Step to the first object.
        do {
            $data = $this->jsonReader->value();
            $this->withProgressBar($data['transactions'], function ($transaction) {
                $this->performImportingTransactions($transaction);
            });

        } while ($this->jsonReader->next() && $this->jsonReader->depth() > $depth); // Read each sibling.

        $this->jsonReader->close();

        $this->info('Transactions imported successfully');
        $this->info('Total transactions: '.$this->totalTransactions);
        $this->info('Inserted transactions: '.$this->insertedTransactions);
        $this->info('Skipped transactions: '.$this->skippedTransactions);
    }

    private function performImportingTransactions($data)
    {
        $user = $this->userRepository->findWhere(['email' => $data['parentEmail']]);
        // insert new transaction only if user is exists
        if ($user) {
            // insert new transaction only if not exists
            $exists = $this->transactionRepository->exists([
                ['parent_identification', '=', $data['parentIdentification']],
                ['parent_email', '=', $data['parentEmail']],
                ['payment_date', '=', $data['paymentDate']]
            ]);
            if ($exists){
                $this->skippedTransactions++;
            }else{
                $this->transactionRepository->create([
                    'paid_amount' => $data['paidAmount'],
                    'currency' => $data['Currency'],
                    'parent_email' => $data['parentEmail'],
                    'status' => TransactionStatus::getConstByCode($data['statusCode']),
                    'status_code' => $data['statusCode'],
                    'payment_date' => $data['paymentDate'],
                    'parent_identification' => $data['parentIdentification'],
                ]);
                $this->insertedTransactions++;
            }

        }else{
            $this->skippedTransactions++;
        }
        $this->totalTransactions++;
    }

}

