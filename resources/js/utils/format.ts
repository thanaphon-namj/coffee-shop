export const numberFormat = (value: number | bigint): string => {
  return Intl.NumberFormat().format(value);
};

export const priceFormat = (value: number | bigint): string => {
  return Intl.NumberFormat("th-TH", {
    style: "currency",
    currency: "THB",
  }).format(value);
};

export const dashFormat = (value: any): string => {
  if (!value) return "-";
  return value;
};
