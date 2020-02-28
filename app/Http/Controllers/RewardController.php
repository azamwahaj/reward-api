<?php

namespace App\Http\Controllers;

use App\Reward;
use App\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class RewardController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createReward(Request $request)
    {
        $reward = Reward::where('name', $request->input('name'))->get()->toArray();

        // check existing reward
        if ($reward) {
            return response()->json(['success' => false, 'message' => 'Reward already exists!']);
        }

        $params['name'] = $request->input('name');
        $params['amount'] = $request->input('amount');
        $params['expiry_date'] = $request->input('expiry_date');

        // create new reward
        $reward = Reward::create($params);

        return response()->json(['success' => false, 'data' => $reward]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateReward(Request $request, $id)
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found!']);
        }

        $reward->name = $request->input('name');
        $reward->amount = $request->input('amount');
        $reward->expiry_date = $request->input('expiry_date');
        $reward->save();

        return response()->json(['success' => true, 'data' => $reward]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReward($id)
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found']);
        }

        $reward->delete();

        return response()->json(['success' => true, 'message' => 'Reward ' . $reward->name . ' removed successfully']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $rewards = Reward::all();

        if (!$rewards) {
            return response()->json(['success' => false, 'message' => 'Rewards not found!']);
        }

        return response()->json(['success' => true, 'data' => $rewards]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReward($id)
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found']);
        }

        $transactions = Transaction::where('rewardId', $id)->get('userId')->toArray();

        if ($transactions) {
            // get user ids from array
            $userIds = Arr::pluck($transactions, 'userId');

            // get list of users who consumed the reward
            $userEndpoint = env('USER_ENDPOINT', '');
            $url = $userEndpoint . '/api/v1/users';

            $client = new \GuzzleHttp\Client();
            $params['userIds'] = $userIds;
            $response = $client->post($url, ['form_params' => $params]);

            if ($response->getStatusCode() == 200) {
                $users = json_decode($response->getBody(), true);
            }
        }

        $reward['users'] = $users['data'] ?? [];

        return response()->json(['success' => true, 'data' => $reward]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignReward(Request $request)
    {
        $params['userId'] = $request->input('userId');
        $params['rewardId'] = $request->input('rewardId');

        $reward = Reward::find($params['rewardId']);
        $transaction = Transaction::where('rewardId', $params['rewardId'])
            ->where('userId', $params['userId'])
            ->get()->toArray();

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found!']);
        }

        if ($reward->expiry_date < date('Y-m-d', time())) {
            return response()->json(['success' => false, 'message' => 'Reward expired!']);
        }

        if ($transaction) {
            return response()->json(['success' => false, 'message' => 'Already rewarded!']);
        }

        // assign reward to user
        Transaction::create($params);

        return response()->json(['success' => true, 'message' => $reward->name . ' assigned successfully']);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function rewardTransactionsByUserId($userId)
    {
        $transactions = DB::table('rewards')
            ->join('transactions', 'rewards.id', '=', 'transactions.rewardId')
            ->select('rewards.*')
            ->where('transactions.userId', $userId)
            ->get()->toArray();

        if (!$transactions) {
            return response()->json(['success' => false, 'message' => 'Transaction not found!']);
        }

        return response()->json(['success' => true, 'data' => $transactions]);
    }
}
