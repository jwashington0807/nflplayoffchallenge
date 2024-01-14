import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { first } from 'rxjs';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';
import { TokenstorageService } from 'src/app/providers/tokenstorage.service';
import { Register } from 'src/app/models/user';

@Component({
  selector: 'app-team',
  templateUrl: './team.component.html',
  styleUrls: ['./team.component.scss']
})
export class TeamComponent {
  displayedColumns = ['Week', 'QB', 'RB1', 'RB2', 'WR1', 'WR2', 'TE', 'K', 'DEF'];

  form!: FormGroup;
  loading = false;
  invalid = false;
  submitted = false;
  isLoggedIn = false;
  isLoginFailed = false;
  errorMessage = '';

  constructor(
    private formBuilder: FormBuilder,
    private accountService: SiteproviderService,
    private tokenStorage: TokenstorageService,
    private router: Router
  ) { }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; } 
  
  onSubmit() {
    this.submitted = true;
    this.invalid = false;

    // stop here if form is invalid
    if (this.form.invalid) {
        return;
    }

    this.loading = true;

    this.accountService.login(this.f['email'].value, this.f['password'].value)
      .pipe(first())
      .subscribe((data: any)  => {

        if(data.token != null) {
          this.tokenStorage.saveToken(data.token);
          this.tokenStorage.saveUser(data);
    
          this.isLoginFailed = false;
          this.isLoggedIn = true;

          // Navigate back to the home page
          window.location.href = "/";
        }
        else {
          this.loading = false;
          this.invalid = true;
          this.errorMessage = data.error;
        }
      });
    }
}
