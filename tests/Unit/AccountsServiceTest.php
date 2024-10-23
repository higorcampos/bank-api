<?php

namespace Tests\Unit;

use App\Models\Accounts;
use App\Models\Transaction;
use App\Repositories\Accounts\AccountsRepositoryContract;
use App\Services\Accounts\AccountsService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class AccountsServiceTest extends TestCase
{
    protected AccountsService $accountsService;
    protected AccountsRepositoryContract|MockInterface $repositoryMock;

    /**
     * Configuração inicial para cada teste.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock do repositório implementando o contrato
        $this->repositoryMock = Mockery::mock(AccountsRepositoryContract::class);
        $this->accountsService = new AccountsService($this->repositoryMock);
    }

    /**
     * Libera os mocks do Mockery após cada teste.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateAccountSuccess()
    {
        $data = ['name' => 'Test Account', 'balance' => 1000];
        $accountMock = Mockery::mock();
        $accountMock->shouldReceive('toArray')->andReturn($data);

        $this->repositoryMock
            ->shouldReceive('create')
            ->with($data)
            ->andReturn($accountMock);

        $result = $this->accountsService->createAccount($data);

        $this->assertEquals($data, $result);
    }

    public function testCreateAccountFailure()
    {
        $data = ['name' => 'Test Account', 'balance' => 1000];

        $this->repositoryMock
            ->shouldReceive('create')
            ->with($data)
            ->andThrow(new \Exception('Error'));

        Log::shouldReceive('error')->once();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(__('exception.create_user'));

        $this->accountsService->createAccount($data);
    }

    public function testGetAccount()
    {
        $accountId = 1;
        $collectionMock = Mockery::mock(Collection::class);

        $this->repositoryMock
            ->shouldReceive('findWhere')
            ->with(['number_account' => $accountId])
            ->andReturn($collectionMock);

        $result = $this->accountsService->getAccount($accountId);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testTransactionSuccess()
    {
        $data = [
            'accounts_id' => 1,
            'payment_method' => 'D',
            'value' => 100,
        ];

        $accountMock = Mockery::mock();
        $accountMock->balance = 1000;
        $accountMock->number_account = '123456';
        $accountMock->shouldReceive('save')->once();

        $this->repositoryMock
            ->shouldReceive('find')
            ->with($data['accounts_id'])
            ->andReturn($accountMock);

        Transaction::shouldReceive('create')
            ->with([
                'accounts_id' => $data['accounts_id'],
                'payment_method' => $data['payment_method'],
                'value' => $data['value'],
            ])
            ->andReturn(Mockery::mock(Transaction::class));

        $result = $this->accountsService->transaction($data);

        $this->assertArrayHasKey('number_account', $result);
        $this->assertArrayHasKey('balance', $result);
        $this->assertArrayHasKey('transaction', $result);
    }
}