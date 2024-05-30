import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UpdateAccComponent } from './update-acc.component';

describe('UpdateAccComponent', () => {
  let component: UpdateAccComponent;
  let fixture: ComponentFixture<UpdateAccComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [UpdateAccComponent]
    });
    fixture = TestBed.createComponent(UpdateAccComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
