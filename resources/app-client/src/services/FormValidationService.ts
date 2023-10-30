import type { validationMatchInterface } from '@/services/types/FormTypes'

export const validateRequired = (val: any): boolean => val && val.length > 0

export const validateMatch = (val: any, type: string): boolean => {
  const matchType: validationMatchInterface = {
    password:
      /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[!/+@#-$%^&*-.])[\w!/+@#-$%^&*-.]{8,20}$/,
    email:
      /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/,
  }

  return val && !!val.match(matchType[type])
}
