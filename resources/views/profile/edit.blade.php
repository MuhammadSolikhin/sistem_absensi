@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-md-12">
        
        <!-- Update Profile Information -->
        <div class="card mb-4">
            <h5 class="card-header">Profile Information</h5>
            <div class="card-body">
                <div class="mb-3">
                     <small class="text-muted">Update your account's profile information and email address.</small>
                </div>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                             <div class="mt-2">
                                <p class="text-muted mb-0">
                                    Your email address is unverified.
                                    <button form="send-verification" class="btn btn-link p-0 align-baseline">Click here to re-send the verification email.</button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="text-success small mt-1">
                                        A new verification link has been sent to your email address.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small fade-out">Saved.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4">
            <h5 class="card-header">Update Password</h5>
            <div class="card-body">
                <div class="mb-3">
                     <small class="text-muted">Ensure your account is using a long, random password to stay secure.</small>
                </div>
                
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" id="current_password" name="current_password" autocomplete="current-password">
                        @if($errors->updatePassword->has('current_password'))
                            <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" id="password" name="password" autocomplete="new-password">
                        @if($errors->updatePassword->has('password'))
                             <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                         @if($errors->updatePassword->has('password_confirmation'))
                             <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success small fade-out">Saved.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card mb-4">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                 <div class="mb-3">
                     <small class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted.</small>
                </div>

                <div class="alert alert-warning">
                    <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                    <p class="mb-0">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                </div>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                    Delete Account
                </button>

                <!-- Modal -->
                <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="post" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')
                                
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Delete Account</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                                    <div class="mb-3">
                                        <label for="password_deletion" class="form-label">Password</label>
                                        <input type="password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" id="password_deletion" name="password" placeholder="Enter your password to confirm">
                                         @if($errors->userDeletion->has('password'))
                                             <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    // Simple script to handle fade out effect for status messages if needed, 
    // though 'session' flash usually handles reload. 
    // We can leave it as is.
</script>
@endsection
