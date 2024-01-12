import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { ProfileComponent } from './pages/profile/profile.component';
import { SignupComponent } from './pages/signup/signup.component';
import { SigninComponent } from './pages/signin/signin.component';
import { TeamComponent } from './pages/team/team.component';
import { LeaderboardComponent } from './pages/leaderboard/leaderboard.component';
import { NotfoundComponent } from './pages/notfound/notfound.component';
import { SiteproviderService } from './providers/siteprovider.service';
import { authGuard } from './helper/auth.guard';
import { SignoutComponent } from './pages/signout/signout.component';

const routes: Routes = [
  { path: '', component: HomeComponent},
  { path: 'profile', component: ProfileComponent, canActivate: [authGuard]},
  { path: 'signup', component: SignupComponent},
  { path: 'signin', component: SigninComponent},
  { path: 'team', component: TeamComponent, canActivate: [authGuard]},
  { path: 'leader', component: LeaderboardComponent, canActivate: [authGuard]},
  { path: 'signout', component: SignoutComponent, canActivate: [authGuard]},
  { path: '**', component: NotfoundComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {useHash: false})],
  exports: [RouterModule],
  providers: [SiteproviderService]
})
export class AppRoutingModule { }
