import { Component } from '@angular/core';
import { SiteproviderService } from './providers/siteprovider.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'nflplayoffsite';

  constructor(private tokenStorageService: SiteproviderService) { }
}
