import { Component } from '@angular/core';
import { first } from 'rxjs';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';
import { TokenstorageService } from 'src/app/providers/tokenstorage.service';

@Component({
  selector: 'app-signout',
  templateUrl: './signout.component.html',
  styleUrls: ['./signout.component.scss']
})
export class SignoutComponent {

  constructor(private service: SiteproviderService, private token: TokenstorageService) {}

  ngOnInit() {
    this.service.logout()
      .pipe(first())
      .subscribe((data: any)  => {

        this.token.signOut();

        // Navigate back to the home page
        window.location.href = "/";
      });
    }
  }

