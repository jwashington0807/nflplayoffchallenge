import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Leaderboard, Profile, Register, Reset, Valid } from '../models/user';
import { BehaviorSubject, Observable } from 'rxjs';
import { Players } from '../models/players';
import { Teams, UserTeam, UserTeamUpdate } from '../models/teams';
import { Forgot } from '../models/user';

const httpOptions = {
  headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable({
  providedIn: 'root'
})

export class SiteproviderService {

  // Variable to hold the link
  private apiPath: string = environment.apiPath;

  // Behavior Objects
  private message = new BehaviorSubject('');
  getMessage = this.message.asObservable();

  private show = new BehaviorSubject(false);
  getShow = this.show.asObservable();

  setMessage(message: string) {
    this.message.next(message);
  }

  setShow(show: boolean) {
    this.show.next(show);

    if(show) {
      setTimeout(() => {
        this.setShow(false);
        this.setMessage("");
      }, 3000);
    }
  }

  // Constructor Method
  constructor(private http: HttpClient) { }

  login(email: string, password: string) {
    return this.http.post(this.apiPath + "/login.php", 
    { email, password }, httpOptions);
  }

  register(registerdata: Register) {
    return this.http.post(this.apiPath + "/register.php", 
    { registerdata }, httpOptions);
  }

  logout() {
    return this.http.post(this.apiPath + "/logout.php", 
    { }, httpOptions);
  }

  getprofile(user: Profile) {
    return this.http.post(this.apiPath + "/profile.php", 
    { user }, httpOptions);    
  }

  setprofile(profile: any) {
    return this.http.post(this.apiPath + "/setprofile.php", 
    { profile }, httpOptions);    
  }

  geteligibleplayers(): Observable<Players[]>{
    return this.http.get<Players[]>(this.apiPath + "/getplayers.php");  
  }

  geteligibleteams(): Observable<Teams[]>{
    return this.http.get<Teams[]>(this.apiPath + "/getteams.php");  
  }

  getuserweekteam(week: number, emailaddress: string) {
    return this.http.get<UserTeam>(this.apiPath + "/getuserweekteam.php?week=" + week + "&useremail=" + emailaddress);
  }

  getweekeligible(week: number) {
    return this.http.get<any>(this.apiPath + "/getweeklystatus.php?week=" + week);
  }

  setuserlineup(userTeam: UserTeamUpdate) {
    return this.http.post(this.apiPath + "/setuserlineup.php", 
    { userTeam }, httpOptions); 
  }

  getplayerrosters(email: any) {
    throw new Error('Method not implemented.');
  }

  forgot(forgot: Forgot) {
    return this.http.post(this.apiPath + "/forgotpassword.php", 
    { forgot }, httpOptions);
  }

  resetValid(data: Valid) {
    return this.http.post(this.apiPath + "/uservalid.php", 
            { data }, httpOptions);
  }

  reset(data: Reset) {
    return this.http.post(this.apiPath + "/userreset.php", 
            { data }, httpOptions);
  }

  getleaderboard() {
    return this.http.get<Leaderboard[]>(this.apiPath + "/leaderboard.php");
  }
 }
