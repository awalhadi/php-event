@extends('layouts.guest')

@section('title', 'Login')
@section('content')
<div class="offset-xl-1">
  <h4 class="fw-normal mb-3 pb-3 mt-4 pt-4" style="letter-spacing: 1px;">Log in</h4>
  <form>
      <!-- Email input -->
      <div data-mdb-input-init class="form-outline mb-4">
          <input type="email" id="form1Example13" class="form-control form-control-lg" />
          <label class="form-label" for="form1Example13">Email address</label>
      </div>

      <!-- Password input -->
      <div data-mdb-input-init class="form-outline mb-4">
          <input type="password" id="form1Example23" class="form-control form-control-lg" />
          <label class="form-label" for="form1Example23">Password</label>
      </div>

      <div class="d-flex justify-content-around align-items-center mb-4">
          <!-- Checkbox -->
          <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="form1Example3"
                  checked />
              <label class="form-check-label" for="form1Example3"> Remember me </label>
          </div>
          <a href="#!">Forgot password?</a>
      </div>

      <!-- Submit button -->
      <button type="submit" data-mdb-button-init data-mdb-ripple-init
          class="btn btn-primary btn-lg btn-block">Sign in</button>

      <div class="divider d-flex align-items-center my-4">
          <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
      </div>
  </form>
  <p class="mb-5 pb-lg-2">Don't have an account? <a href="{{ route('register.show') }}" class="link-info">Register
          here</a></p>
</div>
@endsection
