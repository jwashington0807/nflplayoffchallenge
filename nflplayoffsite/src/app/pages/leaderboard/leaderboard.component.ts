import { Component } from '@angular/core';
import { Leaderboard } from 'src/app/models/user';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';

@Component({
  selector: 'app-leaderboard',
  templateUrl: './leaderboard.component.html',
  styleUrls: ['./leaderboard.component.scss']
})
export class LeaderboardComponent {

  constructor(private accountService: SiteproviderService) {}

  leaderboard: Leaderboard[] = [];

  ngOnInit() {

    //Get all teams and scores
    this.accountService.getleaderboard().subscribe(x => {
      this.leaderboard = x;
    });

  }
}
