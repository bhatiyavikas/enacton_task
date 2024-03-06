<?php

use App\Models\Prize;

$current_probability = floatval(Prize::sum('probability'));
?>

{{-- TODO: add Message logic here --}}

@if((100 - $current_probability) != 0) 
	<div class="alert alert-danger">
	    Sum of all prizes probability must be 100%. Currently its <?=$current_probability?>% You have yet to add <?=(100 - $current_probability)?>%. to the prize.
	</div>
@endif
 