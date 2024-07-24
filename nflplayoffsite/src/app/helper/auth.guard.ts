import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { TokenstorageService } from '../providers/tokenstorage.service';

export const authGuard: CanActivateFn = (route, state) => {

  const router = inject(Router);
  const service = inject(TokenstorageService);
  const token = service.getToken();

  console.log('auth guard');
  console.log('token', token);

  if (token) {
      // authorized
      return true;
  }
  else {
      // not logged in so redirect to login page with the return url
      router.navigate(['/']);
      return false;
  }
};
