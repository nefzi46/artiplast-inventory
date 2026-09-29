cat > resources/views/backend/users/create.blade.php << 'EOF'
@extends('admin.layouts.master')

@section('admin')
    <div class="content">
        <div class="container-xxl">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Ajouter un utilisateur</h4>
                </div>
                <div class="text-end">
                    <a href="{{ route('users.index') }}" class="btn btn-dark">Retour à la liste</a>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations de l'utilisateur</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.store') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-md-6">
                                    <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Adresse email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control"
                                           minlength="6" required>
                                    <small class="text-muted">Minimum 6 caractères</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           minlength="6" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control form-select" required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="phone" class="form-control"
                                           value="{{ old('phone') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Adresse</label>
                                    <textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
EOF
