<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{
    /**
     * GET /api/players
     *
     * Returns all players ordered by vote_count descending.
     */
    public function index(): AnonymousResourceCollection
    {
        $players = Player::orderByDesc('vote_count')->get();

        return PlayerResource::collection($players);
    }

    /**
     * POST /api/players/{player}/vote
     *
     * Increments vote_count for the given player.
     * Prevents the same IP from voting for the same player more than once per hour.
     */
    public function vote(Request $request, Player $player): JsonResponse
    {
        $ip = $request->ip();

        // Check for a vote from this IP for this player in the last 60 minutes
        $alreadyVoted = Vote::where('player_id', $player->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'message' => 'You have already voted for this player in the last hour.',
            ], 429);
        }

        // Record the vote
        Vote::create([
            'player_id'  => $player->id,
            'ip_address' => $ip,
            'created_at' => now(),
        ]);

        // Atomically increment the denormalised counter
        $player->increment('vote_count');
        $player->refresh();

        return response()->json([
            'message' => 'Vote recorded successfully.',
            'player'  => new PlayerResource($player),
        ]);
    }
}
