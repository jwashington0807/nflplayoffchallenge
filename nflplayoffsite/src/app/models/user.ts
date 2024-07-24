export class Register {
    first?: string;
    last?: string;
    email?: string;
    password?: string;
    team?: string;
}

export class Profile {
    first?: string;
    last?: string;
    email?: string;
    team?: string;
}

export class Forgot {
    email?: string;
}

export class Valid {
    key?: string;
    email?: string;
}

export class Reset {
    password1?: string;
    email?: string;
}

export class ResetQueryString {
    key?: string;
    email?: string;
    action?: string;
}

export interface Leaderboard {
    team?: string;
    score?: string;
}