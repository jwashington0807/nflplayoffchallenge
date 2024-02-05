import { Component } from '@angular/core';
import { FormArray, FormBuilder, Validators } from '@angular/forms';
import { SiteproviderService } from 'src/app/providers/siteprovider.service';

@Component({
  selector: 'app-leaderboard',
  templateUrl: './leaderboard.component.html',
  styleUrls: ['./leaderboard.component.scss']
})
export class LeaderboardComponent {

  form = this.formBuilder.group({
    userteams: this.formBuilder.array([])
  });

  constructor(private formBuilder: FormBuilder, private accountService: SiteproviderService) {}

  get userteams() {
    return this.form.controls["userteams"] as FormArray;
  }

  addLesson() {

    const teamForm = this.formBuilder.group({
      title: ['', Validators.required],
      level: ['beginner', Validators.required]
    });

    this.userteams.push(teamForm);
  }
}
