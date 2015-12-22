@extends('admin.layout')

@section('title')
Detail User
@stop

@section('page-header')
Detail User: {{ $user->present()->name }}
@stop

@section('content')
	<div id="content">
		<ul>
			<li><strong>First Name</strong> : <i>{{ $user->firstname }}</i></li>
			<li><strong>Last Name</strong> : <i>{{ $user->lastname }}</i></li>
			<li><strong>Address</strong> : <i>{{ $user->address }}</i></li>
			<li><strong>Country</strong> : <i>{{ $user->country }}</i></li>
			<li><strong>Mobile phone</strong> : <i>{{ $user->mobile_phone }}</i></li>
			<li><strong>Birthday</strong> : <i>{{ $user->dob }}</i></li>
			<li><strong>Email</strong> : <i>{{ $user->email }}</i></li>
			<li><strong>Avatar</strong> : <i><img src=" @if($user->avatar != null && isset($user->avatar['thumb'])) {{ asset($user->avatar['thumb']) }} @else {{ asset('uploads/origin/avatar.jpg') }}  @endif" ></i></li>
			<li><strong>Link Profile</strong> : <i><a href="{{ $user->link_profile }}">{{ $user->link_profile }}</a></i></li>
			<li><strong>Infomation</strong> : <i>{{ $user->infomation }}</i></li>
		</ul>
		<a href="{{ route('admin.user.get.index') }}" class="btn btn-default">Back</a>
	</div>
@stop

@section('script')
<script>
	$(document).ready(function() {
		$('input').attr('disabled', true);
	});
</script>
@endsection