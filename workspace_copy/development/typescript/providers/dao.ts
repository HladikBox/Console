import { Injectable } from '@angular/core';
import { SQLite, SQLiteObject } from '@ionic-native/sqlite';
import { AbstractDao } from "./dao.abstract";
import { Http } from '@angular/http';
import { {{fmodel}}Api } from "./channel.api";

@Injectable()
export class {{fmodel}}Dao extends AbstractDao {

    constructor(public http: Http, public sqlite: SQLite) {
        super(sqlite, http);
    }

	{{funclist}}

}