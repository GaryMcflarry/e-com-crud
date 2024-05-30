import { Component } from '@angular/core';
import { MatDialogRef } from '@angular/material/dialog';
import { BehaviorSubject, map, switchMap} from 'rxjs';
import { HUDService } from 'src/app/services/hud.service';
import { UserService } from 'src/app/services/user.service';
import { WebRequestService } from 'src/app/services/web-request.service';

@Component({
  selector: 'app-update-acc',
  templateUrl: './update-acc.component.html',
  styleUrls: ['./update-acc.component.css'],
})
export class UpdateAccComponent {
  //allows for importing other ts files and library features for component use
  constructor(
    public dialogRef: MatDialogRef<UpdateAccComponent>,
    private user: UserService,
    private web: WebRequestService,
    private hud: HUDService,
  ) {}

  //Variables to capture input data for functions
  username: string = '';
  password: string = '';
  contact: string = '';
  disabled: boolean = true;

  /* ================================================================================= */

  //Behavior subject to capture the ID of the user for other Obervables to make use of
  user$ = new BehaviorSubject('');

  //Observable to capture profile data based of user ID
  profile$ = this.user$.asObservable().pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.post('user', { userID: data }).pipe(
        map((data: any) => {
          this.username = data.data.username;
          this.contact = data.data.contact;
          return data;
        })
      );
    })
  );

  /* ================================================================================= */

  // Function to check if input fields are empty
  checkInputs() {
    this.disabled = !(
      this.username.trim() !== '' &&
      this.password.trim() !== '' &&
      this.contact.trim() !== ''
    );
  }

  //Allowing the user to update their information
  update() {
    const formvalues = {
      userID: this.user.getToken().userID,
      username: this.username,
      password: this.password,
      contact: this.contact,
    };
    this.hud.updateUser(formvalues);
    this.dialogRef.close();
  }

  //Allowing the user to delete their account from the database
  deleteAccount() {
    this.hud.removeUser(this.user.getToken().userID);
    this.dialogRef.close();
    this.hud.logOff();
  }

  //Funtion for closing dialog
  exit(): void {
    this.dialogRef.close();
  }

  /* ================================================================================= */

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.user$.next(this.user.getToken().userID);
  }
}
