import { Component, ViewChild, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material';

@Component({
  selector: 'app-team',
  templateUrl: './team.component.html',
  styleUrls: ['./team.component.scss']
})
export class TeamComponent {
  displayedColumns = ['Week', 'QB', 'RB1', 'RB2', 'WR1', 'WR2', 'TE', 'K', 'DEF'];
  dataSource: MatTableDataSource<TeamData>;



}
