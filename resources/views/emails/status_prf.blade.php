<div style="line-height:1.4;">

@if (str_contains($finance->status, 'rejected'))
<p>Your request for this document number ({{ $finance->doc_no }}) has been rejected with the following</p>
<p>comments :</p>

<p> {{ $keterangan }}</p>

<p>Rejected on :</p>


<p>{{ \Carbon\Carbon::now()->format('l, d-m-Y H:i') }} </p>
<br><br>
<p>Thank you in advance!</p>
<p>Best Regards,</p>
<p>Finance Team </p>
@elseif (str_contains($finance->status, 'approved'))
<p>Your request for this documnet number ({{ $finance->doc_no }}) has been approved with the following</p>
<p>comments :</p>

<p> {{ $keterangan }}</p>

<p>Approved on :</p>

<p>{{ \Carbon\Carbon::now()->format('l, d-m-Y H:i') }} </p>
<br><br>
<p>Thank you in advance!</p>
<p>Best Regards,</p>
<p>Finance Team </p>

@endif

</div>