import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Profile, Register } from '../models/user';
import { Observable } from 'rxjs';
import { Players } from '../models/players';
import { Teams, UserTeam, UserTeamUpdate } from '../models/teams';

const httpOptions = {
  headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable({
  providedIn: 'root'
})

export class SiteproviderService {
  getplayerrosters(email: any) {
    throw new Error('Method not implemented.');
  }

  // Variable to hold the link
  private apiPath: string = environment.apiPath;

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
}
