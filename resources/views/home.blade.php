@extends('layouts.app')

@section('title', 'Home')
@section('content')
<div class="d-flex flex-column gap-5">
  <section class="text-center py-5 bg-light">
    <div class="container">
      <h1 class="display-4 fw-bold mb-4">Welcome to EventMaster</h1>
      <p class="lead mb-4">Simplify your event management process</p>
      <a href="/events" class="btn btn-primary btn-lg">
        Explore Events
      </a>
    </div>
  </section>
</div>
@endsection