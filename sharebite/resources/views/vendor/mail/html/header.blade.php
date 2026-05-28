@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'ShareBite')
<img src="https://drive.google.com/uc?export=view&id=1Vnt2v8OLoeUvCF66iqJ7ns6a0aSX3j5G" class="logo" alt="ShareBite Logo" style="width: auto; height: 75px; max-height: 75px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
