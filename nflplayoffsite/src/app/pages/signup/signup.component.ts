import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { first } from 'rxjs';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';
import { TokenstorageService } from 'src/app/providers/tokenstorage.service';
import { Register } from 'src/app/models/user';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.scss']
})
export class SignupComponent {
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

  ngOnInit() {
    this.form = this.formBuilder.group({
        first: ['', [Validators.required]],
        last: ['', [Validators.required]],
        email: ['', [Validators.required, Validators.minLength(1), Validators.email]],
        password: ['', [Validators.required]],
        teamname: ['', [Validators.required]]
    });
  }

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

    // Initialize HTMLForm to hold Elements
    const formData: Register = new Register();
    formData.first = this.f['first'].value;
    formData.last = this.f['last'].value;
    formData.email = this.f['email'].value;
    formData.password = this.f['password'].value;
    formData.team = this.f['teamname'].value;

    this.accountService.register(formData)
      .pipe(first())
      .subscribe((data: any)  => {

      // Check if there was an error with the request
      if(data.error != null) {
        this.errorMessage = data.error;
      }
      else {
        // Navigate back to the home page
        window.location.href = "/";
      }
      });
    }
}
