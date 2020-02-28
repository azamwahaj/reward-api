<?php

class RewardTest extends TestCase
{
    /**
     * /rewards [GET]
     */
    public function testShouldReturnAllRewards()
    {
        $this->get("/api/v1/rewards", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'success',
            'data' => ['*' =>
                [
                    'id',
                    'name',
                    'amount',
                    'expiry_date',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    /**
     * /reward/id [GET]
     */
    public function testShouldReturnReward()
    {
        $this->get("/api/v1/reward/3", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
                'success',
                'data' =>
                    [
                        'id',
                        'name',
                        'amount',
                        'expiry_date',
                        'created_at',
                        'updated_at',
                        'users',
                    ]
            ]
        );
    }

    /**
     * /reward [POST]
     */
    public function testShouldCreateReward()
    {
        $currentDate = date('Y-m-d', time());

        $params = [
            'name' => 'Reward' . time(),
            'amount' => 10,
            'expiry_date' => date('Y-m-d', strtotime("+1 month", strtotime($currentDate))),
        ];

        $this->post("/api/v1/reward", $params, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'success',
                'data' =>
                    [
                        'id',
                        'name',
                        'amount',
                        'expiry_date',
                        'created_at',
                        'updated_at',
                    ]
            ]
        );
    }

    /**
     * /reward/id [PUT]
     */
    public function testShouldUpdateReward()
    {
        $currentDate = date('Y-m-d', time());

        $params = [
            'name' => 'Test Reward',
            'amount' => 10,
            'expiry_date' => date('Y-m-d', strtotime("+1 month", strtotime($currentDate))),
        ];

        $this->put("/api/v1/reward/5", $params, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
                'success',
                'data' =>
                    [
                        'id',
                        'name',
                        'amount',
                        'expiry_date',
                        'created_at',
                        'updated_at',
                    ]
            ]
        );
    }

    /**
     * /reward/id [DELETE]
     */
    public function testShouldDeleteReward()
    {

        $this->delete("/api/v1/reward/10", [], []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * /assign-reward [POST]
     */
    public function testShouldAssignReward()
    {
        $params = [
            'userId' => 1,
            'rewardId' => 11
        ];

        $this->post("/api/v1/assign-reward", $params, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * /reward/transactions/user/id [GET]
     */
    public function testShouldReturnRewardTransactionsByUserId()
    {
        $this->get("/api/v1/reward/transactions/user/1", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
                'success',
                'data' => ['*' =>
                    [
                        'id',
                        'name',
                        'amount',
                        'expiry_date',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]
        );
    }
}
