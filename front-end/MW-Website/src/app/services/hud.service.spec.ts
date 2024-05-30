import { TestBed } from '@angular/core/testing';

import { HUDService } from './hud.service';

describe('HUDService', () => {
  let service: HUDService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HUDService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
