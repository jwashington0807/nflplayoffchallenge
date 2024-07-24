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
	points: number,
	qbmult: number,
	wr1mult: number,
	wr2mult: number,
	rb1mult: number,
	rb2mult: number,
	temult: number,
	pkmult: number,
	defmult: number
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