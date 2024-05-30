import { Component } from '@angular/core';
import { MatDialogRef } from '@angular/material/dialog';
import { HUDService } from 'src/app/services/hud.service';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css'],
  
})
export class SignupComponent {

  //allows for importing other ts files and library features for component use
  constructor(public dialogRef: MatDialogRef<SignupComponent>, private hud : HUDService) {}

  //Variables for sign up input fields
  username: string = '';
  password: string = '';
  contact: string = '';
  branch: string = '';
  formValues : any;

  /* ================================================================================= */

  //function for sign up, also based on if its in the manage page for admin or user creation
  signUp() {
    if (this.hud.isManageRoute()) {
      this.formValues = {
        username : this.username,
        password : this.password,
        contact : this.password,
        admin : "true",
        chosenBranch : this.branch,
      }  
    } else {
      this.formValues = {
      username : this.username,
      password : this.password,
      contact : this.password,
      admin : "false",
      chosenBranch : this.branch,
    }}
    this.hud.signup(this.formValues)
  }

  //button to close dialog
  exit() {
    this.dialogRef.close();
  }

  
}
