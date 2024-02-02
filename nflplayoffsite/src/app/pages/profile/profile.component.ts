import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';
import { TokenstorageService } from 'src/app/providers/tokenstorage.service';
import { Profile } from 'src/app/models/user';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})

export class ProfileComponent {

  form!: FormGroup;
  loading = false;
  invalid = false;
  submitted = false;
  isLoggedIn = false;
  isLoginFailed = false;
  errorMessage = '';
  user = this.tokenStorage.getUser();
  originalteam = '';

  constructor(
    private formBuilder: FormBuilder,
    private accountService: SiteproviderService,
    private tokenStorage: TokenstorageService
  ) { }

  ngOnInit() {
    this.form = this.formBuilder.group({
      first: ['', [Validators.required]],
      last: ['', [Validators.required]],
      teamname: ['', [Validators.required]],
      email: [{ value: '', disabled: true }]
    });

    if(this.user) {

      const profile = new Profile;
      profile.email = this.user.email;
      profile.first = this.user.first;
      profile.last = this.user.last;
      profile.team = this.f['teamname'].value;

      this.accountService.getprofile(profile)
        .pipe(first())
        .subscribe((data: any)  => {

          this.form.setValue({
            first: data[0].firstname,
            last: data[0].lastname,
            email: data[0].email,
            teamname: data[0].teamname
          })

          this.originalteam = data[0].teamname;
        });
      }
    }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; } 

  onSubmit() {

    if(this.f['first'].value != this.user.first 
      || this.f['last'].value != this.user.last
      || this.f['teamname'].value != this.originalteam) 
    {
      const profile = new Profile;
      profile.email = this.user.email;
      profile.first = this.f['first'].value;
      profile.last = this.f['last'].value;
      profile.team = this.f['teamname'].value;

      this.accountService.setprofile(profile)
      .pipe(first())
      .subscribe((data: any)  => {
        this.accountService.setMessage("Profile Updated Successfully");
        this.accountService.setShow(true);
      });
    }
  }
}
