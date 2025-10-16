<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function balance()
    {
        $user = auth()->user();
        return response()->json([
            'user_id' => $user->id,
            'balance' => $user->balance,
        ], 200);
    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|integer|exists:users,id|different:user_id',
            'amount' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();
        $receiver = User::find($request->receiver_id);

        if ($user->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        // Transaction atomique
        return DB::transaction(function () use ($user, $receiver, $request) {
            $user->balance -= $request->amount;
            $user->save();

            $receiver->balance += $request->amount;
            $receiver->save();

            $transaction = Transaction::create([
                'sender_id' => $user->id,
                'receiver_id' => $receiver->id,
                'amount' => $request->amount,
                'status' => 'success',
            ]);

            return response()->json([
                'transaction_id' => $transaction->id,
                'sender_id' => $transaction->sender_id,
                'receiver_id' => $transaction->receiver_id,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at,
            ], 201);
        });
    }

    public function topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();

        // Transaction atomique
        return DB::transaction(function () use ($user, $request) {
            $user->balance += $request->amount;
            $user->save();

            $transaction = Transaction::create([
                'sender_id' => $user->id,
                'receiver_id' => null,
                'amount' => $request->amount,
                'status' => 'success',
            ]);

            return response()->json([
                'user_id' => $user->id,
                'balance' => $user->balance,
                'topup_amount' => $request->amount,
                'created_at' => $transaction->created_at,
            ], 201);
        });
    }

    public function transactions()
    {
        $user = auth()->user();
        $transactions = Transaction::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get();

        return response()->json($transactions, 200);
    }
}
