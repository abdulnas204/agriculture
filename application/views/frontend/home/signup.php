
            <!-- breadcrumb -->
            <nav>
                <div class="nav-wrapper green">
                  <div class="container">
                      <div class="col s12">
                        <a href="#!" class="breadcrumb"><i class="material-icons">home</i></a>
                        <a href="#!" class="breadcrumb">Login</a>
                      </div>
                  </div>
                </div>
            </nav>
            <!-- Content Area -->
            <div class="interior-wrap">
                <div class="interior-container">
                    <div class="container">    
                      <div class="row">
                          <div class="col s12 m6 offset-m3">
                            <div class="card login-wrapper hoverable">
                              <div class="card-content">
                                <form method="post" action="/account" id="create_customer" accept-charset="UTF-8"><input type="hidden" value="create_customer" name="form_type" /><input type="hidden" name="utf8" value="✓" />
                                  <h4 class="center">Create Account</h4>
                                  <div class="input-field">
                                    <label for="FirstName"> First Name </label>
                                    <input type="text" name="customer[first_name]" id="FirstName" autofocus >
                                  </div>

                                  <div class="input-field">
                                    <label for="LastName"> Last Name </label>
                                    <input type="text" name="customer[last_name]" id="LastName" >
                                  </div>

                                  <div class="input-field">
                                    <label for="Email"> Email </label>
                                    <input type="email" name="customer[email]" id="Email" class="" value="" spellcheck="false" autocomplete="off" autocapitalize="off">
                                  </div>

                                  <div class="input-field">
                                    <label for="CreatePassword"> Password </label>
                                    <input type="password" name="customer[password]" id="CreatePassword" class="">
                                  </div>
                                  <p>
                                    <input type="submit" value="Create" class="btn-large z-depth-0">
                                  </p>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
