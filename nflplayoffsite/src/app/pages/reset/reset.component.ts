import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { first } from 'rxjs/operators';
import { emailMatchValidator, passwordMatchValidator } from 'src/app/helper/validators';
import { Reset, ResetQueryString, Valid } from 'src/app/models/user';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';

@Component({
  selector: 'app-reset',
  templateUrl: './reset.component.html',
  styleUrl: './reset.component.scss'
})
export class ResetComponent {

  // Local Variables
  submitted = false;
  enabled = false;
  errorMessage = '';
  resetform!: FormGroup;

  reset: ResetQueryString = new ResetQueryString();
  constructor(private route: ActivatedRoute, private formBuilder: FormBuilder, private accountService: SiteproviderService) {}

  // convenience getter for easy access to form fields
  get f() { return this.resetform.controls; } 

    ngOnInit(): void {

      console.log("HI");
      // Get Query Params to process
      this.reset = {
        key: this.route.snapshot.params['key'],
        email: this.route.snapshot.params['email']
      }
  
      // Build Validation for Form Controls
      this.resetform = this.formBuilder.group({
        password1: ['', { validators: [Validators.required, Validators.minLength(8)] }],
        password2: ['', { validators: [Validators.required] }]
      }, 
      {
        validators: [ passwordMatchValidator('password1', 'password2')]
      });
  
  
      if(this.reset.key != null || this.reset.email != null) {
  
        // Initialize HTMLForm to hold Elements
        const formData: Valid = new Valid();
        formData.key = this.reset.key;
        formData.email = this.reset.email;
  
        // Check if the link is valid
        this.accountService.resetValid(this.reset)
        .pipe(first())
        .subscribe((data: any)  => {
  
          // Check if there was an error with the request
          if(data.error != null) {
            this.errorMessage = data.error;
            this.enabled = false;
          }
          else {
            this.enabled = true;
          }
        });
  
      }
      else {
        this.errorMessage = 'There was an error that prevents this request from being processed'
        this.enabled = false;
      }
    }
  
    onSubmit() {
      this.submitted = true;
  
      // stop here if form is invalid 
      if (this.resetform.invalid) {
          return;
      }
  
      // Initialize HTMLForm to hold Elements
      const formData: Reset = new Reset();
      formData.password1 = this.f['password1'].value;
      formData.email = this.reset.email;
  
      // proceed with account reset
      this.accountService.reset(formData)
      .pipe(first())
      .subscribe((data: any)  => {
  
        // Check if there was an error with the request
        if(data.error != null) {
          this.errorMessage = data.error;
        }
        else {
          // Navigate back to the home page. No error with registration
          this.reloadPage();
        }
      });
    }
  
    reloadPage(): void {
      window.location.href = "/";
    }
}
