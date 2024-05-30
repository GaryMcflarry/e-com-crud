import { Component } from '@angular/core';
import { StoreService } from './services/store.service';
import { UserService } from './services/user.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  // Title of website
  title = 'MW-Website';

  //allows for importing other ts files and library features for component use
  constructor (private user : UserService, private store : StoreService) {}

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.store.getItem('Token');
    this.user.MaintainUser(this.store.getItem('Token'));
  }
}
