<?php

namespace App\Http\Resources;


class PostResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $votes = time() > strtotime($this->competition->announcement_at) ? $this->number_format_short($this->votes->count()) : NULL;
        return [
            'id' => $this->id,
            'description' => $this->description,
            'state' => $this->state,
            'posted_at' => $this->time2str($this->created_at),
            'votes' => $votes,
            "winner" => $this->competition->winner_id == $this->user->id,
            "user" => UserResource::make($this->user),
            'images' => PostMediaResource::collection($this->media),
        ];
    }

}
