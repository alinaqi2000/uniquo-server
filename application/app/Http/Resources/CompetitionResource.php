<?php

namespace App\Http\Resources;

use App\Helpers\CompetitionHelper;
use App\Models\Competition;

class CompetitionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "id" => $this->id,
            "participated" => FALSE,
            "title" => $this->title,
            "description" => $this->description,
            "slug" => $this->slug,
            "paid" => $this->paid == "1",
            "financials" => [
                "entry_fee" => ($this->financial->entry_fee),
                "prize_money" => ($this->financial->prize_money),
                "total_amount" => ($this->financial->total),
            ],
            "participations" => $this->participants()->count(),
            "participants_allowed" => $this->participants_allowed,
            "voting_start_at" => date("M d, Y", strtotime($this->voting_start_at)),
            "voting_time" => date("H:i", strtotime($this->voting_start_at)),
            "announcement_at" => date("M d, Y", strtotime($this->announcement_at)),
            "announcement_time" => date("H:i", strtotime($this->announcement_at)),
            "stage" => CompetitionHelper::getStage(Competition::find($this->id)),
            "expired" => strtotime($this->announcement_at) < time(),
            'category' => CategoryResource::make($this->category),
            "organizer" => UserResource::make($this->organizer),
            "winner" => UserResource::make($this->winner),
        ];
        if ($this->participants()->where("participant_id", auth()->user()->id)->count()) {
            $data['participated'] = TRUE;
        }
        return $data;
    }
}
