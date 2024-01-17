import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Players } from 'src/app/models/players';
import { Teams } from 'src/app/models/teams';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';
import { TokenstorageService } from 'src/app/providers/tokenstorage.service';

@Component({
  selector: 'app-team',
  templateUrl: './team.component.html',
  styleUrls: ['./team.component.scss']
})
export class TeamComponent implements OnInit{

  form!: FormGroup;
  players: Players[] = [];
  teams: Teams[] = [];

  // Selection Controls
  qbselected: number = -1;
  wr1selected: number = -1;
  wr2selected: number = -1;
  rb1selected: number = -1;
  rb2selected: number = -1;
  teselected: number = -1;
  pkselected: number = -1;
  defselected: number = -1;

  // Styling Controls
  wr2style: string = '';

  constructor(
    private formBuilder: FormBuilder,
    private accountService: SiteproviderService,
    private tokenStorage: TokenstorageService
  ) { }

  ngOnInit() {
    this.form = this.formBuilder.group({
        week: [''],
        totalpoints: [{ value: null, disabled: true }],
        teamqb: [''],
        qbmult: [''],
        teamwr1: [''],
        wr1mult: [''],
        teamwr2: [''],
        wr2mult: [''],
        teamrb1: [''],
        rb1mult: [''],
        teamrb2: [''],
        rb2mult: [''],
        teamte: [''],
        temult: [''],
        teamk: [''],
        kmult: [''],
        teamdef: [''],
        defmult: ['']
    });

    //Get all eligible players
    this.accountService.geteligibleplayers().subscribe(x => {
      this.players = x;
    });

    // Get all eligible teams
    this.accountService.geteligibleteams().subscribe(y => {
      this.teams = y;
    });
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; } 
  
  get filterbyQB() {
      return this.players.filter( x => x.position == 'QB');
  }

  get filterbyRB() {
    return this.players.filter( x => x.position == 'RB');
  }

  get filterbyWR() {
    return this.players.filter( x => x.position == 'WR');
  }

  get filterbyTE() {
    return this.players.filter( x => x.position == 'TE');
  }

  get filterbyK() {
    return this.players.filter( x => x.position == 'PK');
  }

  get getTeams() {
    return this.teams;
  }

  getUserWeekTeam(week: number) {

    // Get User Email
    let user = this.tokenStorage.getUser();

    if(user.email) {

      //Get all eligible players
      this.accountService.getuserweekteam(week, user.email).subscribe(x => {

        //Set Fields on Form with Data
        this.qbselected = x.qbid;
        this.wr1selected = x.wr1id;
        this.wr2selected = x.wr2id;
        this.rb1selected = x.rb1id;
        this.rb2selected = x.rb2id;
        this.teselected = x.teid;
        this.pkselected = x.pkid;
        this.defselected = x.defid;

        // Set the Background Color

        this.accountService.getweekeligible(week).subscribe(data => {

            if(data['0'] == 0) {
              // Disable the form
              this.f['teamqb'].disable();
              this.f['teamwr1'].disable();
              this.f['teamwr2'].disable();
              this.wr2style = "background-color: #000";
              this.f['teamrb1'].disable();
              this.f['teamrb2'].disable();
              this.f['teamte'].disable();
              this.f['teamk'].disable();
              this.f['teamdef'].disable();
            }
            else 
            {
              // Enable the form
              this.f['teamqb'].enable();
              this.f['teamwr1'].enable();
              this.f['teamwr2'].enable();
              this.f['teamrb1'].enable();
              this.f['teamrb2'].enable();
              this.f['teamte'].enable();
              this.f['teamk'].enable();
              this.f['teamdef'].enable();
            }
        });
      });
    }
  }

  onSubmit() {

    // stop here if form is invalid
    if (this.form.invalid) {
        return;
    }


    }
}
