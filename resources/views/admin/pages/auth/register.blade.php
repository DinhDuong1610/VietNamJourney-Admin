<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng ký</title>

  <link rel="stylesheet" href={{asset("https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback")}}>
  <link rel="stylesheet" href={{asset("admin-rs/plugins/fontawesome-free/css/all.min.css")}}>
  <link rel="stylesheet" href={{asset("admin-rs/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}>
  <link rel="stylesheet" href={{asset("admin-rs/dist/css/adminlte.min.css")}}>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-success">
    <div class="card-header text-center">
      <h1 class="h1"><b>Việt Nam Journey</b></h1>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.auth.register-post') }}" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control login-input" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control login-input" placeholder="Username" name="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control login-input" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control login-input" placeholder="Confirm password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">Đăng ký</button>
          </div>
        </div>
      </form>
      <p class="mt-2 mb-2 text-center">
        <a href="{{ route('admin.auth.login') }}" class="text-center login-link">Đăng nhập</a>
      </p>
    </div>
  </div>
</div>

<script src={{asset("admin-rs/plugins/jquery/jquery.min.js")}}></script>
<script src={{asset("admin-rs/plugins/bootstrap/js/bootstrap.bundle.min.js")}}></script>
<script src={{asset("admin-rs/dist/js/adminlte.min.js")}}></script>


<style>
  .input-group .login-input:focus {
      border-color: #28A745 !important;
  }

  .login-link {
      color: #28A745 !important;
  }
</style>
</body>
</html>
