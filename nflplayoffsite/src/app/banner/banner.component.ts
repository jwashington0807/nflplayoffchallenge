import { Component } from '@angular/core';
import { TokenstorageService } from '../providers/tokenstorage.service';
import { SiteproviderService } from '../providers/siteprovider.service';

@Component({
  selector: 'app-banner',
  templateUrl: './banner.component.html',
  styleUrls: ['./banner.component.scss']
})
export class BannerComponent {

  // To track if the user is currently logged in
  authenticated: boolean = false;
  isMenuOpen = false;
  showbar: string = "showbar";
  message: string = "";
  show: boolean = false;

  constructor(private token: TokenstorageService, private service: SiteproviderService) {
    this.service.getMessage.subscribe(msg => this.message = msg);
    this.service.getShow.subscribe(sh => this.show = sh);
  }
  
  ngOnInit() {

    const user = this.token.getToken();

    if(user) {
      this.authenticated = true;
    }
  }

  toggleMenu(): void {
    this.isMenuOpen = !this.isMenuOpen;
  }
}
