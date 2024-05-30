import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';

const routes: Routes = [
// Forcing path to hit login
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  //If path is hit will go to selected component
  {path: 'login', component: LoginComponent,},
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AuthRoutingModule {}
