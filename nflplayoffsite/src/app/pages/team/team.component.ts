import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Players } from 'src/app/models/players';
import { Teams, UserTeamUpdate } from 'src/app/models/teams';
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

  // Form Controls 
  btndisable = false;

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
        qbmult: [{ value: null, disabled: true }],
        teamwr1: [''],
        wr1mult: [{ value: null, disabled: true }],
        teamwr2: [''],
        wr2mult: [{ value: null, disabled: true }],
        teamrb1: [''],
        rb1mult: [{ value: null, disabled: true }],
        teamrb2: [''],
        rb2mult: [{ value: null, disabled: true }],
        teamte: [''],
        temult: [{ value: null, disabled: true }],
        teamk: [''],
        kmult: [{ value: null, disabled: true }],
        teamdef: [''],
        defmult: [{ value: null, disabled: true }]
    });

    //Get all eligible players
    this.accountService.geteligibleplayers().subscribe(x => {
      this.players = x;
    });

    // Get all eligible teams
    this.accountService.geteligibleteams().subscribe(y => {
      this.teams = y;
    });

    this.btndisable = true;
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

      // Get all eligible players
      this.accountService.getuserweekteam(week, user.email).subscribe(x => {

        //Set Fields on Form with Data
        x.qbid != null ? this.qbselected = x.qbid : this.qbselected = -1;
        x.wr1id != null ? this.wr1selected = x.wr1id : this.wr1selected = -1;
        x.wr2id != null ? this.wr2selected = x.wr2id : this.wr2selected = -1;
        x.rb1id != null ? this.rb1selected = x.rb1id : this.rb1selected = -1;
        x.rb2id != null ? this.rb2selected = x.rb2id : this.rb2selected = -1;
        x.teid != null ? this.teselected = x.teid : this.teselected = -1;
        x.pkid != null ? this.pkselected = x.pkid : this.pkselected = -1;
        x.defid != null ? this.defselected = x.defid : this.defselected = -1;

        if(x.points != null){
          this.f['totalpoints'].setValue(x.points);
        }
        else {
          this.f['totalpoints'].setValue(0);
        }
        
        this.f['qbmult'].setValue("x" + x.qbmult);
        this.f['wr1mult'].setValue("x" + x.wr1mult);
        this.f['wr2mult'].setValue("x" + x.wr2mult);
        this.f['rb1mult'].setValue("x" + x.rb1mult);
        this.f['rb2mult'].setValue("x" + x.rb2mult);
        this.f['temult'].setValue("x" + x.temult);
        this.f['kmult'].setValue("x" + x.pkmult);
        this.f['defmult'].setValue("x" + x.defmult);
      });

      // Check if week is enabled
      this.accountService.getweekeligible(week).subscribe(data => {

        if(data['0'] == 0) {
          // Disable the form
          this.f['teamqb'].disable();
          this.f['teamwr1'].disable();
          this.f['teamwr2'].disable();
          this.f['teamrb1'].disable();
          this.f['teamrb2'].disable();
          this.f['teamte'].disable();
          this.f['teamk'].disable();
          this.f['teamdef'].disable();
          this.btndisable = true;
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
          this.btndisable = false;
        }
      });
    }
  }

  onSubmit() {

    // stop here if form is invalid
    if (this.form.invalid) {
        return;
    }

    // Get User Email
    let user = this.tokenStorage.getUser();

    const userTeam = new UserTeamUpdate();
    userTeam.email = user.email;
    userTeam.week = this.f['week'].value;
    userTeam.qbid = this.qbselected;
    userTeam.wr1id = this.wr1selected;
    userTeam.wr2id = this.wr2selected;
    userTeam.rb1id = this.rb1selected;
    userTeam.rb2id = this.rb2selected;
    userTeam.teid = this.teselected;
    userTeam.pkid = this.pkselected;
    userTeam.defid = this.defselected;

    this.accountService.setuserlineup(userTeam).subscribe(y => {
      this.accountService.setMessage("Roster Updated Successfully");
      this.accountService.setShow(true);
    });
  }
}
