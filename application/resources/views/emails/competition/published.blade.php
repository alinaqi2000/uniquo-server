@component('mail::message', ["title" => $data["title"]])
### Hurray!,
<br>

{{ $data['competition']['slug']  }} is live now! Share with friends and followers to get maximum participations.
@component('mail::table')
| | |
| ------------------------ |:-------------------------------------------------------------: |
| <b>Title</b>             | {{ $data['competition']['title'] }} |
| <b>Tag</b>               | #{{ $data['competition']['slug'] }} |
| <b>Announcement Date</b> | {{ date(config("constants.date.format"), strtotime($data['competition']['announcement_at'])) }} |
| <b>Voting Date</b>       | {{ date(config("constants.date.format"), strtotime($data['competition']['voting_start_at'])) }} |
@endcomponent

<br>
<br>

Thanks, <br>
{{ config('app.name') }}'s Team
@endcomponent
