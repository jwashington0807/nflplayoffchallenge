import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';
import { Forgot } from 'src/app/models/user';
import { first } from 'rxjs/operators';

@Component({
  selector: 'app-forgot',
  templateUrl: './forgot.component.html',
  styleUrls: ['./forgot.component.scss']
})

export class ForgotComponent {

  form!: FormGroup;
  submitted = false;
  completed = false;
  errorMessage = '';
  
  constructor(
    private formBuilder: FormBuilder,
    private accountService: SiteproviderService
  ) { } 

  ngOnInit() {
    this.form = this.formBuilder.group({
        email: ['', [Validators.required, Validators.minLength(1), Validators.email]]
    });
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; } 

  onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.form.invalid || this.completed) {
        return;
    }

    // Initialize HTMLForm to hold Elements
    const formData: Forgot = new Forgot();
    formData.email = this.f['email'].value;

    this.accountService.forgot(formData)
      .pipe(first())
      .subscribe((data: any)  => {

        this.accountService.setMessage("Please check your email to reset your password");
        this.accountService.setShow(true);

        // Disable button
        this.completed = true;
    });
  }
}
