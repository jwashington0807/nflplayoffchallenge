import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Profile, Register } from '../models/user';

const httpOptions = {
  headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable({
  providedIn: 'root'
})

export class SiteproviderService {

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


}
