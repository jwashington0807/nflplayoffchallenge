export interface Teams {
    teamsid: number,
	teamname: string
}

export interface UserTeam {
    userid: number,
	qbid: number,
	wr1id: number,
	wr2id: number,
	rb1id: number,
	rb2id: number,
	teid: number,
	pkid: number,
	defid: number,
	points: number
}

export class UserTeamUpdate {
    email?: number;
	week?: number;
	qbid?: number;
	wr1id?: number;
	wr2id?: number;
	rb1id?: number;
	rb2id?: number;
	teid?: number;
	pkid?: number;
	defid?: number;
}