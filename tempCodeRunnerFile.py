import numpy as np
from scipy import stats

CS = [100, 71, 93, 86, 97, 51, 81, 83]
NB = [85, 81, 95, 96, 86, 72, 85, 86]
MRI = [50, 48, 72, 40, 91, 44, 52, 42]

CS_NB, CS_NB = stats.ttest_ind(CS, NB)
CS_MRI, MRI = stats.ttest_ind(CS, MRI)
NB_MRI, NB_MRI = stats.ttest_ind(NB, MRI)

print("Hasil uji t antara Clinical Symptoms (CS) dan Naive Bayes (NB):")
print("Nilai t-statistik:", CS_NB)
print("Nilai p-value:", CS_NB)
print()

print("Hasil uji t antara Clinical Symptoms (CS) dan MRI:")
print("Nilai t-statistik:", CS_MRI)
print("Nilai p-value:", CS_MRI)
print()

print("Hasil uji t antara Naive Bayes (NB) dan MRI:")
print("Nilai t-statistik:", NB_MRI)
print("Nilai p-value:", NB_MRI)