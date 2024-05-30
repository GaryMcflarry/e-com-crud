import { Component } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { SignupComponent } from '../signup/signup.component';
import { HUDService } from 'src/app/services/hud.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent {
  //allows for importing other ts files and library features for component use
  constructor(
    public dialog: MatDialog,
    private hud: HUDService,
  ) {}

  //Variables to keep track of fields
  username: string = '';
  password: string = '';
  disabled: boolean = true;

  /* ================================================================================= */

  // Function to check if input fields are empty
  checkInputs() {
    this.disabled = !(
      this.username.trim() !== '' && this.password.trim() !== ''
    );
  }

  // Function to handle login
  login() {
    const formValues = {
      username: this.username,
      password: this.password,
    };
    this.hud.login(formValues);
  }

  //Function to open us sign up dialog
  openDialog(
    enterAnimationDuration: string,
    exitAnimationDuration: string
  ): void {
    this.dialog.open(SignupComponent, {
      enterAnimationDuration,
      exitAnimationDuration,
    });
  }
}
