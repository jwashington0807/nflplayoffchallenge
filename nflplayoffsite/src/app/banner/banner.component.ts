import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { TokenstorageService } from '../providers/tokenstorage.service';

@Component({
  selector: 'app-banner',
  templateUrl: './banner.component.html',
  styleUrls: ['./banner.component.scss']
})
export class BannerComponent {

  constructor(private router: Router, private token: TokenstorageService) {}

  // To track if the user is currently logged in
  authenticated: boolean = false;
  isMenuOpen = false;

  ngOnInit() {

    const user = this.token.getToken();

    if(user) {
      this.authenticated = true;
    }

  }

  /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
  toggleMenu(): void {
    this.isMenuOpen = !this.isMenuOpen;
  }
}
